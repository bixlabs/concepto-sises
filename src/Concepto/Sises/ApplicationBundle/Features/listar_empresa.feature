# language: es
Característica: Listar empresas
  Como usuario del sistema
  Necesito poder ver el listado de empresas

  Antecedentes:
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa

  Escenario: veo un listado de empresas
    Dado que obtengo un listado de empresas
    Entonces existe una empresa de nombre "DINCO"