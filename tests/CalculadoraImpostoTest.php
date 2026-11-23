<?php

namespace NFePHP\DuaEs\Tests;

use NFePHP\DuaEs\CalculadoraImposto;
use PHPUnit\Framework\TestCase;

class CalculadoraImpostoTest extends TestCase
{
    private const XML_EXEMPLO = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<nfeProc xmlns="http://www.portalfiscal.inf.br/nfe">
    <NFe>
        <infNFe Id="NFe00000000000000000000000000000000000000" versao="4.00">
            <det nItem="1">
                <imposto>
                    <ICMSUFDest>
                        <vICMSUFDest>10.00</vICMSUFDest>
                        <vFCPUFDest>2.50</vFCPUFDest>
                    </ICMSUFDest>
                    <ICMS>
                        <ICMS00>
                            <vICMSST>5.00</vICMSST>
                        </ICMS00>
                    </ICMS>
                </imposto>
            </det>
            <det nItem="2">
                <imposto>
                    <ICMSUFDest>
                        <vICMSUFDest>3.00</vICMSUFDest>
                        <vFCPUFDest>0.50</vFCPUFDest>
                    </ICMSUFDest>
                </imposto>
            </det>
        </infNFe>
    </NFe>
</nfeProc>
XML;

    public function test_extrai_valores_por_item(): void
    {
        $itens = (new CalculadoraImposto())->extrairPorItem(self::XML_EXEMPLO);

        $this->assertCount(2, $itens);
        $this->assertSame(10.0, $itens[0]['v_icms_uf_dest']);
        $this->assertSame(2.5, $itens[0]['v_fcp_uf_dest']);
        $this->assertSame(5.0, $itens[0]['v_icms_st']);
        $this->assertSame(0.0, $itens[1]['v_icms_st']);
    }

    public function test_soma_total_difal(): void
    {
        $calculadora = new CalculadoraImposto();
        $itens = $calculadora->extrairPorItem(self::XML_EXEMPLO);

        $this->assertSame(16.0, $calculadora->totalDifal($itens));
    }

    public function test_soma_total_icms_st(): void
    {
        $calculadora = new CalculadoraImposto();
        $itens = $calculadora->extrairPorItem(self::XML_EXEMPLO);

        $this->assertSame(5.0, $calculadora->totalIcmsSt($itens));
    }
}
