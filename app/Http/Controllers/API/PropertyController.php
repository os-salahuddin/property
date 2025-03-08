<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Mime\Part\Multipart\MixedPart;

class PropertyController extends BaseController
{
    /**
    * Display a listing of the resource.
    * https://medium.com/@vidvatek/laravel-10-rest-api-authentication-using-sanctum-d94a861a5ef9
    *
    * @return \Illuminate\Http\Response
    */
    public function index(): Mixed
    {
        $properties = Property::all();
        return $this->sendResponse(PropertyResource::collection($properties), 'Products retrieved successfully.');
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    
    public function store(Request $request): Mixed
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $property = Property::create($input);
        
        return $this->sendResponse(new PropertyResource($property), 'Property created successfully.');
    } 
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    
    public function show($id): Mixed
    {
        $property = Property::find($id);
        if (is_null($property)) {
            return $this->sendError('Property not found.');
        }
        return $this->sendResponse(new PropertyResource($property), 'Property retrieved successfully.');
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Property $property): Mixed
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $property->name = $input['name'];
        $property->address = $input['address'];
        $property->save();
        
        return $this->sendResponse(new PropertyResource($property), 'Property updated successfully.');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Property $property): Mixed
    {
        $property->delete();
        return $this->sendResponse([], 'Property deleted successfully.');
    }
}