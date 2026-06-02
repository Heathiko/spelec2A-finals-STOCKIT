<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;
use Core\Validator;

final class CategoryController extends Controller{
    public function __construct(private readonly CategoryRepository $categories, private readonly Validator $validator) {
    }

    public function index(Request $request): Response
    {
        return $this->view('categories/index', [
            'title' => 'Categories',
            'categories' => $this->categories->all(),
        ]);
    }

    private function rules(): array{
        return [
            'name'=> ['required', 'string', 'max:255'],
            'description'=>['required', 'string']
        ];
    }

    private function normalized(array $data): array{
        return [
            'name'=> (string) $data['name']
        ];
    }


    private function payload(Request $request): array{
        return [
            'name'=> $request->input('name'),
            'description'=>$request->input('description')
        ];
    }

  public function create(Request $request): Response {
    return $this->view('categories/create', [
        'title' => 'Create Category',
        'errors' => [],
        'old' => [],
    ]);
}


    public function store(Request $request):Response {
        $data = $this->payload($request);

        if(!$this->validator->Validate($data, $this->rules())){
            return $this->view('categories/create', [
                'title' => 'Create Category',
                'errors' => $this->validator->errors(),
                'old' => $data,
            ], 400);
        }

        $this->categories->create($data);
        return $this->redirect('/categories');

    }




}
