<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Entity\Imagenes;
use App\Form\ImagenGaleriaType;
use App\Services\MyLogs;
use proyecto\app\exceptions\FileException;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImagenGaleriaController extends AbstractController
{
    /**
     * @Route("/imagenes-galeria", name="imagen")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imagen = new Imagenes();
        $formularioImagenes = $this->createForm(ImagenGaleriaType::class, $imagen);
        $formularioImagenes->handleRequest($request);
        $arrayImagenes = $entityManager->getRepository(Imagenes::class)->findAll();
        $errores = [];

        if ($formularioImagenes->isSubmitted() && $formularioImagenes->isValid()) {//si esta enviado y es valido

            $nombre = $formularioImagenes->get('nombre')->getData();
            $categoria = $formularioImagenes->get('categoria')->getData();
            $descripcion = $formularioImagenes->get('descripcion')->getData();

            if (empty($nombre)) {
                array_push($errores, 'No se ha seleccionado ninguna imagen.');
            }
            if (empty($categoria)) {
                array_push($errores, 'No se ha seleccionado ninguna categoría.');
            }
            if (empty($descripcion)) {
                array_push($errores, 'La descripción no puede quedar vacía.');
            }

            if (empty($errores)) {
                $brochureFile = $formularioImagenes->get('nombre')->getData();//guardamos los datos del campo nombre
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
                    $imagen->setNombre($newFilename);
                }
                $descripcion = $formularioImagenes['descripcion']->getData();//Guardamos la descripcion
                $categoriaId = $formularioImagenes['categoria']->getData(); //Sacamos la categoria
                $imagen->setDescripcion($descripcion);

                $categoria = $entityManager->getRepository(Categorias::class)->find($categoriaId);//Buscamos la categoria mediante el id del formulario
                $categoria->setNumImagenes($categoria->getNumImagenes() + 1);//Le decimos que añada +1 a la categoria que nos viene por el formulario

                $mensajeLog = 'Se ha guardado una nueva IMAGEN con el NOMBRE: ' . $imagen->getNombre();//Creamos el mensaje de log que vamos a guardar
                $log = new MyLogs('IMAGEN'); //Creamos un nuevo log al que le pasamos el nombre
                $log->add($mensajeLog); //Añadimos el log con el mensaje anterior creado al fichero de logs

                $entityManager->persist($imagen);
                $entityManager->persist($categoria);
                $entityManager->flush();
                $this->addFlash('Exito', 'Se ha subido una nueva imagen.');
                return $this->redirectToRoute('imagen');
            }
        }

        return $this->render('imagen/index.html.twig', [
            'controller_name' => 'ImagenGaleriaController',
            'arrayImagenes' => $arrayImagenes,
            'errores' => $errores,
            'formulario' => $formularioImagenes->createView(),
        ]);
    }


    /**
     * @Route("/imagenes-galeria/{id}", name="showImagenGaleria")
     */
    public function verImagenes($id)//Metodo para ver una imagen mediante el id que nos pasan por parametro
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imagen = $entityManager->getRepository(Imagenes::class)->find($id);//Buscamos por id

        return $this->render('imagen/showImagenGaleria.html.twig', [
            'imagen' => $imagen,
        ]);
    }
}
