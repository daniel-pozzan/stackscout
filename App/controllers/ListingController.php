<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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

        $requiredFields = ['title', 'description', 'email', 'city', 'state'];

        $errors = [];

        foreach($requiredFields AS $field){
            if(empty($newListingsData[$field]) || !Validation::string($newListingsData[$field])){
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if(!empty($errors)){
            // Reload view with errors
            loadView('listings/create', [
                'errors' => $errors,
                'listings' => $newListingsData
            ]);
        } else {
            // Submit data
            $fields = [];

            foreach($newListingsData AS $field => $value){
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach($newListingsData AS $field => $value){
                // Convert empty strings into nulls
                if($value === ''){
                    $newListingsData[$field] = null;
                }
                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ($values)";

            $this->db->query($query, $newListingsData);

            redirect('/listings');

        }
        
    }


}