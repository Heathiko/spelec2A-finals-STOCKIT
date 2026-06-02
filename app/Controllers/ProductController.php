<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;
use Core\Validator;

final class ProductController extends Controller {

    public function __construct(
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
        private readonly Validator $validator,
    ) {
    }



    public function index(Request $request): Response {
        return $this->view('products/index', [
            'title'=>'Products',
            'products'=>$this->products->all(),
        ]);
    }

    public function show(Request $request): Response {
        $id = (int) $request->route('id');
        $product = $this->products->find($id);


        if($product==null){
            return $this->view('products/show', [
                'title'=>'Product not found.',
                'product'=> null
            ], 400);
        }


        return $this->view('products/show', [
            'title' =>$product['name'],
            'product'=> $product,
        ]);
    }


    //--------------------------------------


    //////NORMALIZE
    /** @param array<string, mixed> $data */
    private function normalized(array $data): array {
        return [
            'category_id'=> (int) $data['category_id'],
            'name' => (string) $data['name'],
            'sku' => (string) $data['sku'],
            'description' => (string) ($data['description'] ?? ''),
            'quantity' => (int) $data['quantity'],
            'price' => (float) $data['price'],
        ];
    }


    //PAYLOAD
    private function productPayload(Request $request): array{
        return [
            'category_id' => $request->input('category_id'),
            'name' => trim((string) $request->input('name', '')),
            'sku' => trim((string) $request->input('sku', '')),
            'description' => trim((string) $request->input('description', '')),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
        ];
    }


    //RULES
    private function rules(): array{
        return [
            'category_id' => 'required|numeric',
            'name' => 'required|string',
            'sku' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function update(Request $request): Response{
        $id = (int) $request->route('id');
        $data = $this->productPayload($request);

        if (!$this->validator->validate($data, $this->rules())) {
            $product = $this->products->find($id) ?? ['id' => $id];

            return $this->view('products/edit', [
                'title' => 'Edit Product',
                'product' => array_merge($product, $this->normalized($data)),
                'categories' => $this->categories->all(),
                'errors' => $this->validator->errors(),
            ], 422);
        }

        $this->products->update($id, $this->normalized($data));

        return $this->redirect('/products/' . $id);
    }

    public function destroy(Request $request): Response{
        $id = (int) $request->route('id');
        $this->products->delete($id);

        return $this->redirect('/products');
    }






    //BUKOG:
    public function create(Request $request): Response {
    return $this->view('products/create', [
        'title' => 'Add Product',
        'categories' => $this->categories->all(),
        'errors' => [],
        'old' => [],
    ]);
}
    public function store(Request $request): Response{
        $data = $this->productPayload($request);

        if (!$this->validator->validate($data, $this->rules())) {
            return $this->view('products/create', [
                'title' => 'Add Product',
                'categories' => $this->categories->all(),
                'errors' => $this->validator->errors(),
                'old' => $data,
            ], 422);
        }

        $this->products->create($this->normalized($data));

        return $this->redirect('/products');
    }

    public function edit(Request $request): Response{
        $id = (int) $request->route('id');
        $product = $this->products->find($id);

        if ($product === null) {
            return Response::html('<h1>Product not found</h1>', 404);
        }

        return $this->view('products/edit', [
            'title' => 'Edit Product',
            'product' => $product,
            'categories' => $this->categories->all(),
            'errors' => [],
        ]);
    }






}