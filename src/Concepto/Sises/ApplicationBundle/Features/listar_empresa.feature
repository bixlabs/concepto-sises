# language: es
Característica: Listar empresas
  Como usuario del sistema
  Necesito poder ver el listado de empresas

  Antecedentes:
    Dado que no hay empresas
    Dado una nueva empresa
    Dado el nit "800234567-1"
    Dado el nombre "DINCO"
    Entonces crea una nueva empresa

  Escenario: veo un listado de empresas
    Dado que obtengo un listado de empresas
    Entonces existe una empresa de nombre "DINCO"