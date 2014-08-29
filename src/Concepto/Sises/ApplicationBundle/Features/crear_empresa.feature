# language: es
Característica: Crear empresa
  Como usuario del sistema
  Necesito poder crear una empresa

#  Antecedentes:
#    Dado que existe una persona "Jose", "Arias", "1112233445"

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

