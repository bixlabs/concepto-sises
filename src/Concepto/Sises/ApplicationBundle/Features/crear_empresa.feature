# language: es
Característica: Crear empresa
  Como usuario del sistema
  Necesito poder crear una empresa

  Antecedentes:
    Dado que no hay empresas

  Escenario: nueva empresa sin nombre
    Dado una nueva empresa
    Dado el nit "800234567-1"
    Entonces crea una nueva empresa respuesta invalida

  Escenario: nueva empresa sin nit
    Dado una nueva empresa
    Dado el nombre "DINCO"
    Entonces crea una nueva empresa respuesta invalida

  Esquema del escenario: nueva empresa
    Dada una nueva empresa
    Dado el nombre <nombre>
    Dado el nit <nit>
    Dado el logo <logo>
    Dado el telefono <telefono>
    Dada la dirección <direccion>
    Dado el correo electrónico <email>
    Entonces crea una nueva empresa

  Ejemplos:
    | nombre   | nit           | logo            | telefono   | direccion         | email           |
    | "DINCO"  | "800234567-1" | "dincologo.png" | "55800000" | "CL 1000 # 35-23" | "info@dinco.co" |

