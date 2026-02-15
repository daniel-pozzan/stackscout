<?php

namespace App\Controllers;

use Framework\Database;

class ListingController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show all listings
     *
     * @return void
     */
    public function index() {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();

        loadView('listings/index', [
            'listings' => $listings
        ]);
    }

    /**
     * Create new listing
     *
     * @return void
     */
    public function create() {
        loadView('listings/create');
    }

    /**
     * Show a listing
     *
     * @return void
     */
    public function show($params) {
        $id = $params['id'] ?? '';
        
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        if(!$listing){
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * Store data in database
     * 
     * @return void
     */
    public function store() {
        $allowedFields = [
            'title', 
            'description', 
            'salary', 
            'requirements', 
            'benefits', 
            'company', 
            'address', 
            'city', 
            'state', 
            'phone', 
            'email'
        ];
        $newListingsData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingsData['user_id'] = 1;

        $newListingsData = array_map('sanitize', $newListingsData);
        inspectAndDie($newListingsData);
    }


}