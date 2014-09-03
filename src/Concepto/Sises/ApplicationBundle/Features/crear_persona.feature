# language: es
@persona
Característica: Crear persona
  Como usuario del sistema
  Necesito poder crear un persona

  Antecedentes:
    Dado que no hay personas

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