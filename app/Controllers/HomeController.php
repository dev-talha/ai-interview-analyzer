<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index', ['categories' => array_slice(Category::all('id ASC'), 0, 6)]);
    }
}
