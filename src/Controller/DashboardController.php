<?php

namespace App\Controller;

use App\Entity\Asociados;
use App\Entity\Categorias;
use App\Entity\Imagenes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        //Sacamos los asociados
        $arrayAsociados = $this->sacarAsociados();
        /*
         * Sacamos 3 arrays que le pasaremos a la plantilla mediante el render para asi poder mostrar las imagenes por
         * las categoriass correspondientes.
         */
        $arrayImagenesCategoria1 = $this->sacarImagenesCategorias(1);
        $arrayImagenesCategoria2 = $this->sacarImagenesCategorias(2);
        $arrayImagenesCategoria3 = $this->sacarImagenesCategorias(3);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'arrayAsociados' => $arrayAsociados,
            'arrayImagenesCategoria1' => $arrayImagenesCategoria1,
            'arrayImagenesCategoria2' => $arrayImagenesCategoria2,
            'arrayImagenesCategoria3' => $arrayImagenesCategoria3,
        ]);
    }


    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about/index.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    /**
     * @Route("/post", name="post")
     */
    public function posts(): Response
    {
        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    //Metodo que nos retorna un array con los asociados dependiendo de los que haya dentro
    public function sacarAsociados(){
        $entityManager = $this->getDoctrine()->getManager();
        $arrayAsociados = $entityManager->getRepository(Asociados::class)->findAll();//todos los asociados

        if (count($arrayAsociados) >= 3) {//Si hay mas de 3
            shuffle($arrayAsociados);//Los mezclamos
            return (array_slice($arrayAsociados, 0,3));//Retornamos 3 de ellos
        } else {//Si no hay mas de 3 retornamos los que haya
            return $arrayAsociados;
        }
    }

    /*
     * Este metodo sirve para retornar las imagenes en un array dependiendo de la categorias
     * que le pasamos como parametro.
     */
    public function sacarImagenesCategorias($categoria) {
        $entityManager = $this->getDoctrine()->getManager();
        return $entityManager->getRepository(Imagenes::class)->findBy(['categoria' => $categoria]);
    }

}
