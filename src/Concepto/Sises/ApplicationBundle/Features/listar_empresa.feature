# language: es
@empresa
Característica: Listar empresa
  Como usuario del sistema
  Necesito poder ver el listado de empresa

  Antecedentes:
    Dado que no hay contratos
    Dado que no hay empresas
    Dado una nueva "empresa"
    Dado el "nit" de la "empresa" "800234567"
    Dado el "nombre" de la "empresa" "DINCO"
    Entonces crea una nueva "empresa"

  Escenario: veo un listado de "empresa"
    Dado que obtengo un listado de "empresa"
    Entonces existe una "empresa" de "nombre" "DINCO"