# Changelog

## 0.1.0-alpha

### Formatadores

-   Método para deixar apenas caracteres númericos em uma string (**toOnlyNumbers**)
-   Método que formata uma string para o formato de telefone (**toPhoneNumber**)
-   Método que formata uma string para o formato de CEP (**toCep**)
-   Método que formata uma string para o formato de CPF/CNPJ (**toCpfCnpj**)
-   Método que limpa todos os caracteres especias para comparação, ideal para busca em banco de dados (**normalizeTextToCompare**)
-   Método para transformar números para o formato decimal, como "1" para "01" (**toDecimalString**)
-   Método para comparar se dois textos são o mesmo ignorando maísculas, mínusculas e caracteres especiais (**isSameString**)
-   Método para verificar se uma string contém apenas caracteres númericos (**hasOnlyNumbers**)
-   Método para formatar um número em valor monetário (**toCurrency**)
-   Método para transformar cada primeira letra de uma string em maíscula (**capitalizeWords**)

### Máscaras

-   Suporte para mascarar CPF/CNPJ, e-mails e telefones

### Validações

-   Suporte para validar CPF e CNPJ

### Regras de validação de requisições

-   Suporte a validações para campos que contenham: CEP, CPF, CNPJ telefones, códigos das Unidades Federativas (UF) e se o campo é somente númerico

### Objeto de resposta

-   Objeto de resposta de erro padronizado conforme o RFC 9457 (Problem Datails)
-   Objeto de resposta auxiliar para padronizar todas as respostas de sucesso de uma aplicação Laravel

## 0.2.1-alpha

-   Nova regra de validação para códigos de município do IBGE adicionada (CodigoMunicipioIbge )
-   Objetos de resposta ProblemDetails e SuccessResponse, corrigidos

## 0.3.1-dev

-   Novo método de formatação para PIS/PASEP adicionado (`toPisPasep`)
-   Nova máscara para PIS/PASEP adicionada (`pispasep`)
-   Nova regra de validação para PIS/PASEP adicionada (`isValidPisPasep`)
-   Nova regra de validação para formulários para PIS/PASEP adicionada (`PisPasep`)
