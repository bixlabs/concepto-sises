# language: es
@beneficiario
Característica: Crear beneficiario
  Como usuario del sistema
  Necesito poder crear un beneficiario

  Antecedentes:
    Dado que no hay contratos
    Dado que no hay empresas
    Dado que no hay beneficiarios
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa
    Dado un nuevo contrato
    Dado el "nombre" del contrato "CONTRATO 1"
    Dado la "descripcion" del contrato "CONTRATO DE ALIMENTACION"
    Dado la "resolucion" del contrato "1024 de Diciembre de 2014"
    Dado el "valor" del contrato 15345678765.43
    Entonces crea un nuevo contrato


  Escenario: Crear un beneficiario
    Dado un nuevo beneficiario
    Dado el "nombre" del beneficiario "JUAN ALBERTO"
    Dado los "apellidos" del beneficario "PEREZ ALBAREZ"
    Dado el "documento" del beneficiario "1111222333"
    Entonces crea un nuevo beneficiario

  Escenario: Crear un beneficiario documento duplicado
    Dado un nuevo beneficiario
    Dado el "nombre" del beneficiario "JUAN ALBERTO"
    Dado los "apellidos" del beneficario "PEREZ ALBAREZ"
    Dado el "documento" del beneficiario "1111222333"
    Entonces crea un nuevo beneficiario
    Dado un nuevo beneficiario
    Dado el "nombre" del beneficiario "PEDRO"
    Dado los "apellidos" del beneficario "SUAREZ MEJIA"
    Dado el "documento" del beneficiario "1111222333"
    Entonces crea un nuevo beneficiario invalido