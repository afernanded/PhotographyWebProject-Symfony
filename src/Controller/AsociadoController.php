<?php

namespace App\Controller;

use App\Entity\Asociados;
use App\Form\AsociadosType;
use App\Services\MyLogs;
use proyecto\app\exceptions\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AsociadoController extends AbstractController
{
    /**
     * @Route("/asociado", name="asociado")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $asociado = new Asociados();
        $form = $this->createForm(AsociadosType::class, $asociado);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $arrayAsociados = $entityManager->getRepository(Asociados::class)->findAll();
        $errores = [];


        if ($form->isSubmitted() && $form->isValid()) {
            $nombre = $form->get('nombre')->getData();
            $brochureFile = $form->get('logo')->getData();
            $descripcion = $form->get('descripcion')->getData();

            if (empty($nombre)) {
                array_push($errores, 'El nombre no puede quedar vacío.');
            }
            if (empty($brochureFile)) {
                array_push($errores, 'No se ha seleccionado ninguna imagen.');
            }
            if (empty($descripcion)) {
                array_push($errores, 'La descripción no puede quedar vacía.');
            }

            if (empty($errores)) {
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new \Exception('Error al subir la imagen');
                    }
                    $asociado->setLogo($newFilename);
                }

                $mensajeLog = 'Se ha guardado un nuevo ASOCIADO con el NOMBRE: ' . $asociado->getNombre();//Creamos el mensaje de log que vamos a guardar
                $log = new MyLogs('ASOCIADO'); //Creamos un nuevo log al que le pasamos el nombre
                $log->add($mensajeLog); //Añadimos el log con el mensaje anterior creado al fichero de logs

                $entityManager->persist($asociado);
                $entityManager->flush();
                $this->addFlash('Exito', Asociados::ASOCIADO_CORRECTO);
                return $this->redirectToRoute('asociado');
            }
        }

        return $this->render('asociado/index.html.twig', [
            'controller_name' => 'AsociadoController',
            'arrayAsociados' => $arrayAsociados,
            'errores' => $errores,
            'formulario' => $form->createView(),
        ]);

    }
}
