<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Area;
use App\Models\Unit;
use App\Models\Type;

class AreaController extends Controller
{
    private $route = 'area';
    private $title = 'Saha';
    private $fillables = ['name','unit_price'];
    private $fillables_titles = ['Isim','Fiyat'];
    private $fillables_types = ['text','text','one','one'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::all();
        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => $this->fillables,
            'fillables_titles' => $this->fillables_titles,
            'empty_space' => 700,
            'data' => $areas
        );
        return view($this->route.'.index')->with($my_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();        
        $units = Unit::all();        
        if(count($types) == 0)
            return redirect()->route('type.create');
        if(count($units) == 0)
            return redirect()->route('unit.create');
        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => ['name','unit_price' ,$types, $units],
            'fillables_titles' => ['Isim','Fiyat','Tipler','Unite'],
            'fillables_types' => $this->fillables_types,
            'is_multiple' => false
        );        
        return view($this->route.'.create')->with($my_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $area = new Area;
        $area->name = $request->name;        
        $area->unit_price = $request->unit_price;        
        $area->type()->associate($request->types); 
        $area->unit()->associate($request->units);         
        $area->save();                         
        return redirect()->route($this->route.'.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        $types = Type::all();
        $insertedTypesIds = array();                            
        array_push($insertedTypesIds, $area->type->id);

        $units = Unit::all();
        $insertedUnitIds = array();                            
        array_push($insertedUnitIds, $area->unit->id);


        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => ['name','unit_price',[$types, $insertedTypesIds], [$units, $insertedUnitIds] ],
            'fillables_titles' => ['Isim','Fiyat','Tipler','Uniteler'],  
            'fillables_types' => $this->fillables_types,          
            'data' => $area
        );
        return view($this->route.'.edit')->with($my_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $area->name = $request->name;
        $area->unit_price = $request->unit_price;
        $area->type()->associate($request->types);          
        $area->unit()->associate($request->units);
        $area->save();        
        return redirect()->route($this->route.'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route($this->route.'.index');
    }
}