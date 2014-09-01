# language: es
@empresa
Característica: Crear empresa
  Como usuario del sistema
  Necesito poder crear una empresa

  Antecedentes:
    Dado que no hay empresas

  Escenario: nueva empresa sin nombre
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Entonces crea una nueva empresa respuesta invalida

  Escenario: nueva empresa sin nit
    Dado una nueva empresa
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa respuesta invalida

  Escenario: crear una empresa mismo nit invalido
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO"
    Entonces crea una nueva empresa
    Dado una nueva empresa
    Dado el "nit" de la empresa "800234567-1"
    Dado el "nombre" de la empresa "DINCO 2"
    Entonces crea una nueva empresa respuesta invalida

  Esquema del escenario: nueva empresa
    Dada una nueva empresa
    Dado el "nombre" de la empresa <nombre>
    Dado el "nit" de la empresa <nit>
    Dado el "logo" de la empresa <logo>
    Dado el "telefono" de la empresa <telefono>
    Dado el "direccion" de la empresa <direccion>
    Dado el "email" de la empresa <email>
    Entonces crea una nueva empresa

  Ejemplos:
    | nombre   | nit           | logo            | telefono   | direccion         | email           |
    | "DINCO"  | "800234567-1" | "dincologo.png" | "55800000" | "CL 1000 # 35-23" | "info@dinco.co" |

