<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'idArticle';

    protected $allowedFields = ['titreArticle', 'contenue', 'dateArticle'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
