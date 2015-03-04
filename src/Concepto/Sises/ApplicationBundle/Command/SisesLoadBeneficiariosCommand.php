<?php


namespace Concepto\Sises\ApplicationBundle\Command;


use Concepto\Sises\ApplicationBundle\Entity\Beneficiario;
use Concepto\Sises\ApplicationBundle\Entity\Beneficio;
use Concepto\Sises\ApplicationBundle\Entity\EntityRepository;
use Concepto\Sises\ApplicationBundle\Entity\LugarEntrega;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\ORM\EntityManager;
use Goodby\CSV\Import\Standard as CSV;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SisesLoadBeneficiariosCommand extends ContainerAwareCommand
{

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var CSV\Lexer
     */
    private $lexer;

    /**
     * @var string
     */
    private $file;

    const COL_CODIGO_UBICACION = 2;
    const COL_DOCUMENTO = 8;
    const COL_1_NOM = 11;
    const COL_2_NOM = 12;
    const COL_1_APE = 9;
    const COL_2_APE = 10;
    const COL_NOM_LUGAR = 5;

    const BAG_UBICACION = 'ubicacion';
    const BAG_LUGAR = 'lugar';
    const BAG_PERSONA = 'persona';

    private $bag;

    protected function configure()
    {
        $this->setName('sises:load:beneficiarios')
            ->setDescription("Carga datos de prueba")
            ->addOption('template', 't', InputOption::VALUE_REQUIRED, "Configuracion del archivo")
            ->addArgument('file', InputArgument::OPTIONAL, "Ruta del archivo a cargar")
            ->addArgument('service', InputArgument::REQUIRED, "Id del servicio a cargar")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->file = $input->getArgument('file');

        $this->bag = array(
            self::BAG_UBICACION => array(),
            self::BAG_LUGAR => array(),
            self::BAG_PERSONA => array()
        );

        $config = new CSV\LexerConfig();
        $config
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->setFromCharset('UTF-8')
            ->setToCharset('UTF-8')
        ;

        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->lexer = new CSV\Lexer($config);

        $servicio = $this->manager
            ->getRepository('SisesApplicationBundle:ServicioContratado')
            ->findOne($input->getArgument('servicio'));

        $this->loadData($servicio);
    }

    private function loadData(ServicioContratado $servicio)
    {
        $manager = $this->manager;
        $bag = &$this->bag;

        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository('SisesApplicationBundle:Ubicacion\CentroPoblado');

        $findLugar = function($columns) use ($repo, &$bag, $manager) {
            $nombre = $columns[self::COL_NOM_LUGAR];
            $codigoLugar = $columns[self::COL_CODIGO_UBICACION];

            if (!isset($bag[self::BAG_UBICACION][$nombre])) {

                if (!isset($bag[self::BAG_LUGAR][$codigoLugar])) {
                    $bag[self::BAG_LUGAR][$codigoLugar] = $repo->findOneBy(array('codigoDane' => "{$codigoLugar}000"));
                }

                $lugar = new LugarEntrega();
                $lugar->setUbicacion($bag[self::BAG_LUGAR][$codigoLugar]);
                $lugar->setNombre($nombre);
                $manager->persist($lugar);

                $bag[self::BAG_UBICACION][$nombre] = $lugar;
            }

            return $bag[self::BAG_UBICACION][$nombre];
        };

        /** @var EntityRepository $repo2 */
        $repo2 = $this->manager->getRepository('SisesApplicationBundle:Persona');
        $findPersona = function($column) use ($repo2, &$bag, $manager) {
            $documento = $column[self::COL_DOCUMENTO];

            // Busca la persona en la base d datos
            if (!isset($bag[self::BAG_PERSONA][$documento])) {
                $bag[self::BAG_PERSONA][$documento] = $repo2->findOneBy(array('documento' => $documento));
            }

            // Crea la persona
            if (!isset($bag[self::BAG_PERSONA][$documento])) {
                $persona = new Persona();
                $persona->setNombre(trim(
                    "{$column[self::COL_1_NOM]} {$column[self::COL_2_NOM]}"
                ));
                $persona->setApellidos(trim(
                    "{$column[self::COL_1_APE]} {$column[self::COL_2_APE]}"
                ));
                $persona->setDocumento($documento);
                $manager->persist($persona);

                $bag[self::BAG_PERSONA][$documento] = $persona;
            }

            return $bag[self::BAG_PERSONA][$documento];
        };

        $interpreter = new CSV\Interpreter();
        $interpreter->addObserver(function (array $columns) use ($bag, $findLugar, $findPersona, $servicio, $manager) {
            if ($columns[0] == "AÑO") {
                return;
            }

            $beneficiario = new Beneficiario();
            $beneficiario->setPersona($findPersona($columns));
            $beneficiario->setContrato($servicio->getContrato());

            $beneficio = new Beneficio();
            $beneficio->setBeneficiario($beneficiario);
            $beneficio->setLugar($findLugar($columns));
            $beneficio->setServicio($servicio);

            $manager->persist($beneficiario);
            $manager->persist($beneficio);
        });

        $this->lexer->parse($this->file, $interpreter);

        ksort($bag['ubicacion']);

        $manager->flush();
    }
}