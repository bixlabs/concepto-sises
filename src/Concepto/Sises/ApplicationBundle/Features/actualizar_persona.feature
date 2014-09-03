# language: es
@persona
Característica: actualizar persona
  Como usuario del sistema
  Necesito poder actualizar una persona existente

  Antecedentes:
    Dado que no hay personas
    Dado un nuevo persona
    Dado el "nombre" del persona "JUAN ALBERTO"
    Dado los "apellidos" del persona "PEREZ ALBAREZ"
    Dado el "documento" del persona "1111222333"
    Entonces crea un nuevo persona

  Escenario: actualizar un solo campo
    Dado un nuevo persona
    Dado el "nombre" del persona "JUAN PABLO"
    Entonces actualiza la persona