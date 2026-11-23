<?php

namespace NFePHP\DuaEs\Tests;

use NFePHP\DuaEs\CodigoDua;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CodigoDuaTest extends TestCase
{
    public function test_st_por_apuracao_fora_do_es(): void
    {
        $this->assertSame('137-6', CodigoDua::paraStPorApuracao(false));
    }

    public function test_st_por_apuracao_dentro_do_es(): void
    {
        $this->assertSame('138-4', CodigoDua::paraStPorApuracao(true));
    }

    public function test_st_por_operacao(): void
    {
        $this->assertSame('137-6', CodigoDua::paraStPorOperacao());
    }

    public function test_difal_lanca_exception_por_codigo_nao_confirmado(): void
    {
        $this->expectException(RuntimeException::class);

        CodigoDua::paraDifal();
    }
}
