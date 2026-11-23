<?php

namespace NFePHP\DuaEs;

use RuntimeException;
use SimpleXMLElement;

/**
 * Extrai e soma, a partir do XML de uma NF-e, os valores de DIFAL e ICMS-ST
 * por item — sem nenhuma dependência de framework. Mesma lógica de extração
 * usada em pacotes da família GNRE (ICMSUFDest/vICMSUFDest + vFCPUFDest para
 * DIFAL, vICMSST para ST).
 */
class CalculadoraImposto
{
    private const NAMESPACE_NFE = 'http://www.portalfiscal.inf.br/nfe';

    /**
     * @return array<int, array{numero_item: int, v_icms_uf_dest: float, v_fcp_uf_dest: float, v_icms_st: float}>
     */
    public function extrairPorItem(string $xmlNfe): array
    {
        $estadoAnterior = libxml_use_internal_errors(true);

        try {
            $sx = simplexml_load_string($xmlNfe, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOCDATA);

            if (! $sx instanceof SimpleXMLElement) {
                throw new RuntimeException('Não foi possível interpretar o XML da NF-e.');
            }

            $sx->registerXPathNamespace('nfe', self::NAMESPACE_NFE);
            $infNFe = $sx->xpath('//nfe:infNFe')[0] ?? null;

            if (! $infNFe instanceof SimpleXMLElement) {
                throw new RuntimeException('O XML informado não contém os dados de uma NF-e.');
            }

            $infNFe->registerXPathNamespace('nfe', self::NAMESPACE_NFE);
            $itens = [];

            foreach ($infNFe->xpath('./nfe:det') ?: [] as $det) {
                $det->registerXPathNamespace('nfe', self::NAMESPACE_NFE);
                $itens[] = [
                    'numero_item' => (int) ($det['nItem'] ?? 0),
                    'v_icms_uf_dest' => $this->valorFloat($det, './nfe:imposto/nfe:ICMSUFDest/nfe:vICMSUFDest'),
                    'v_fcp_uf_dest' => $this->valorFloat($det, './nfe:imposto/nfe:ICMSUFDest/nfe:vFCPUFDest'),
                    'v_icms_st' => $this->valorFloat($det, './nfe:imposto/nfe:ICMS/*/nfe:vICMSST'),
                ];
            }

            return $itens;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($estadoAnterior);
        }
    }

    public function totalDifal(array $itensExtraidos): float
    {
        return array_sum(array_map(
            static fn (array $item): float => $item['v_icms_uf_dest'] + $item['v_fcp_uf_dest'],
            $itensExtraidos
        ));
    }

    public function totalIcmsSt(array $itensExtraidos): float
    {
        return array_sum(array_column($itensExtraidos, 'v_icms_st'));
    }

    private function valorFloat(SimpleXMLElement $det, string $xpath): float
    {
        $no = $det->xpath($xpath)[0] ?? null;

        return $no === null ? 0.0 : (float) trim((string) $no);
    }
}
