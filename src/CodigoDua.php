<?php

namespace NFePHP\DuaEs;

use RuntimeException;

/**
 * Mapeamento dos códigos GNRE para os códigos equivalentes do DUA-ES.
 *
 * Fonte: tabela de correspondência GNRE x DUA publicada pela Sefaz-ES
 * (https://sefaz.es.gov.br/receita-estadual/GNRE/informacoes). Só os códigos
 * de ICMS-ST estão confirmados — ver README.md sobre o DIFAL (EC 87/2015).
 */
class CodigoDua
{
    private const ST_APURACAO_FORA_ES = '137-6';

    private const ST_APURACAO_DENTRO_ES = '138-4';

    private const ST_OPERACAO = '137-6';

    /**
     * ICMS-ST recolhido por apuração (equivalente ao código GNRE 10004-8).
     */
    public static function paraStPorApuracao(bool $contribuinteDoEs): string
    {
        return $contribuinteDoEs ? self::ST_APURACAO_DENTRO_ES : self::ST_APURACAO_FORA_ES;
    }

    /**
     * ICMS-ST recolhido por operação (equivalente ao código GNRE 10009-9).
     */
    public static function paraStPorOperacao(): string
    {
        return self::ST_OPERACAO;
    }

    /**
     * DIFAL (EC 87/2015). Código ainda não confirmado — ver README.md.
     *
     * @throws RuntimeException sempre, até o código oficial ser confirmado.
     */
    public static function paraDifal(): string
    {
        throw new RuntimeException(
            'Código DUA para DIFAL (EC 87/2015) ainda não confirmado. Consulte a seção '
            .'"Substituição Tributária e DIFAL - EC 87" em sefaz.es.gov.br e contribua com a '
            .'confirmação — ver README.md.'
        );
    }
}
