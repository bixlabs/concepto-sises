# language: es
@persona
Característica: Crear persona
  Como usuario del sistema
  Necesito poder crear un persona

  Antecedentes:
    Dado que no hay contratos
    Dado que no hay empresas
    Dado que no hay personas
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


  Escenario: Crear un persona
    Dado un nuevo persona
    Dado el "nombre" del persona "JUAN ALBERTO"
    Dado los "apellidos" del persona "PEREZ ALBAREZ"
    Dado el "documento" del persona "1111222333"
    Entonces crea un nuevo persona

  Escenario: Crear un persona documento duplicado
    Dado un nuevo persona
    Dado el "nombre" del persona "JUAN ALBERTO"
    Dado los "apellidos" del persona "PEREZ ALBAREZ"
    Dado el "documento" del persona "1111222333"
    Entonces crea un nuevo persona
    Dado un nuevo persona
    Dado el "nombre" del persona "PEDRO"
    Dado los "apellidos" del persona "SUAREZ MEJIA"
    Dado el "documento" del persona "1111222333"
    Entonces crea un nuevo persona invalido