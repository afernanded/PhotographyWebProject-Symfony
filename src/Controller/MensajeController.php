<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Form\MensajesType;
use App\Services\MyLogs;
use App\Services\MyMail;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MensajeController extends AbstractController
{
    /**
     * @Route("/mensaje", name="mensaje")
     */
    public function index(Request $request, Swift_Mailer $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $mensaje = new Mensajes();
        $formularioMensaje = $this->createForm(MensajesType::class,$mensaje);
        $formularioMensaje->handleRequest($request);
        $errores = [];
        if ($formularioMensaje->isSubmitted() && $formularioMensaje->isValid()) {
            $nombre = $formularioMensaje['nombre']->getData();
            $apellidos = $formularioMensaje['apellidos']->getData();
            $asunto = $formularioMensaje['asunto']->getData();
            $email = $formularioMensaje['email']->getData();
            $texto = $formularioMensaje['texto']->getData();
            if (empty($nombre)) {
                array_push($errores, 'El Nombre no puede quedar vacío.');
            }
            if (empty($asunto)) {
                array_push($errores, 'El Asunto no puede quedar vacío.');
            }
            if (empty($email)) {
                array_push($errores, 'El Email no puede quedar vacío.');
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                array_push($errores, 'El Email introducido no es válido.');
            }
            if (empty($texto)) {
                array_push($errores, 'La descripción no puede quedar vacía.');
            }
            if (empty($errores)) {
                $mensaje->setNombre($nombre);
                $mensaje->setApellidos($apellidos);
                $mensaje->setAsunto($asunto);
                $mensaje->setEmail($email);
                $mensaje->setTexto($texto);
                $mensaje->setFecha(new \DateTime());

                $mensajeLog = 'Se ha guardado un nuevo MENSAJE con el ASUNTO: ' . $mensaje->getAsunto();//Creamos el mensaje de log que vamos a guardar
                $log = new MyLogs('CONTACTO'); //Creamos un nuevo log al que le pasamos el nombre
                $log->add($mensajeLog); //Añadimos el log con el mensaje anterior creado al fichero de logs

                $mail = new MyMail(); //Creamos un nuevo Mail
                $mail->send($mensaje->getAsunto(), $mensaje->getEmail(), $mensaje->getNombre(), $mensaje->getTexto()); //Enviamos el mail con los datos pasados por el form

                $entityManager->persist($mensaje);
                $entityManager->flush();
                $this->addFlash('Exito', 'Se ha enviado un nuevo mensaje.');
                return $this->redirectToRoute('mensaje');
            }
        }

        return $this->render('mensaje/index.html.twig', [
            'controller_name' => 'MensajeController',
            'errores' => $errores,
            'formulario' => $formularioMensaje->createView()
        ]);
    }
}
