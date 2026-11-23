# sped-dua-es

Pacote PHP, no padrão da comunidade [nfephp-org](https://github.com/nfephp-org), para **calcular**
o DIFAL/ICMS-ST devido ao Espírito Santo e montar os dados corretos (valor, código DUA) para
preenchimento do **DUA** (Documento Único de Arrecadação) da Sefaz-ES.

## Por que este pacote existe

Desde 2010 a Sefaz-ES **não aceita GNRE diretamente** — valores que em outros estados são pagos
via `nfephp-org/sped-gnre` precisam, para o ES, ser convertidos para um código DUA e pagos através
dele. Fonte: [GNRE — informações, SEFAZ-ES](https://sefaz.es.gov.br/receita-estadual/GNRE/informacoes).

## Status atual — ⚠️ incompleto, leia antes de usar

O que existe hoje:

- `CodigoDua`: mapeamento GNRE → DUA **confirmado apenas para ICMS-ST**:
  - ST por apuração (equivalente GNRE `10004-8`) → DUA `137-6` (contribuinte de fora do ES) ou
    `138-4` (contribuinte do ES)
  - ST por operação (equivalente GNRE `10009-9`) → DUA `137-6`
- `CalculadoraImposto`: soma, a partir do XML de uma NF-e, os campos de imposto relevantes para
  DIFAL (`ICMSUFDest/vICMSUFDest` + `vFCPUFDest`) e para ICMS-ST (`vICMSST`) por item — mesma
  lógica de extração usada em outros pacotes da família GNRE, mas sem dependência de framework.

O que **não** existe ainda, e por quê:

- **Código DUA para DIFAL (EC 87/2015).** A Sefaz-ES tem uma seção própria sobre
  "Substituição Tributária e DIFAL — EC 87" cujo código específico não foi confirmado na pesquisa
  que originou este pacote. `CodigoDua::paraDifal()` lança exceção até alguém confirmar o código
  oficial.
- **Emissão/transmissão automática do DUA.** A Sefaz-ES só oferece o portal manual
  "[Sefaz-e-DUA](https://sefaz.es.gov.br/dua-documento-unico-de-arrecadacao)" — não há webservice
  público. Este pacote **não tenta simular o preenchimento do formulário web** (automação de
  portal sem API oficial é frágil e pode ser considerada indevida) — ele só calcula os valores e
  o código correto para você preencher manualmente no portal.
- **Geração de código de barras.** O layout de código de barras de arrecadação estadual segue uma
  especificação própria (FEBRABAN/convênio de arrecadação) que não foi confirmada para o DUA-ES —
  gerar um código de barras errado é pior do que não gerar nenhum, por isso não há esse método
  aqui.

## Como contribuir

Se você confirmar o código DUA de DIFAL, ou encontrar documentação sobre o layout do código de
barras do DUA, abra uma issue ou PR.

## Licença

LGPL-3.0-or-later / GPL-3.0-or-later / MIT — mesmo esquema de licenciamento usado pelos demais
pacotes `nfephp-org/sped-*`.
