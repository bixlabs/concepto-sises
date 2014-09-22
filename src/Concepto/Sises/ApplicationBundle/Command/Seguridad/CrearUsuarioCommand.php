<?php
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */

namespace Concepto\Sises\ApplicationBundle\Command\Seguridad;
use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class CrearUsuarioCommand
 * @package Concepto\Sises\ApplicationBundle\Command\Seguridad
 *
 */
class CrearUsuarioCommand extends ContainerAwareCommand
{
    const USERNAME = 'usuario';
    const PASSWORD = 'password';

    protected function configure()
    {
        $this
            ->setName('concepto:seguridad:usuario:crear')
            ->setDescription('Agrega un usuario')
            ->addArgument(self::USERNAME, InputArgument::REQUIRED, "Usuario del sistema")
            ->addOption(self::PASSWORD, 'p', InputOption::VALUE_REQUIRED, "Contraseña de usuario")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === ($password = $input->getOption(self::PASSWORD))) {
            $dialog = $this->getHelper('dialog');
            $password = $dialog->askHiddenResponse($output, '<info>Digite contraseña?</info> ');
        }

        if (!$password) {
            throw new \Exception("La contraseña es requerida!");
        }

        $usuario = new Usuario();
        $usuario->setNombre($input->getArgument(self::USERNAME));

        // Codifica y setea la contraseña
        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($usuario);
        $usuario->setPassword($encoder->encodePassword($password, $usuario->getSalt()));

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $em->persist($usuario);
        $em->flush();

        $output->writeln("Usuario <info>{$usuario->getUsername()}</info> creado satisfactoriamente!");
    }
}