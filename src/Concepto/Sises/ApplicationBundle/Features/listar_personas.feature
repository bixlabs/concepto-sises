# language: es
@persona
Característica: Listar personas
  Como usuario del sistema
  Necesito poder ver el listado de personas

  Antecedentes:
    Dado que no hay personas
    Dado un nuevo persona
    Dado el "nombre" del persona "JUAN ALBERTO"
    Dado los "apellidos" del persona "PEREZ ALBAREZ"
    Dado el "documento" del persona "1111222333"
    Entonces crea un nuevo persona

  Escenario: veo un listado de contratos
    Dado que obtengo un listado de personas
    Entonces existe un persona de "nombre" "JUAN ALBERTO"