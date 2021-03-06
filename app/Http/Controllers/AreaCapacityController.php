<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AreaCapacity;
use App\Models\Area;
use App\Models\Plant;
use App\Models\Unit;


class AreaCapacityData{
    public $id;
    public $capacity;
    public $plant;
    public $area;
}

class AreaCapacityController extends Controller
{
    private $route = 'area-capacity';
    private $title = 'Saha Kapasite';
    private $fillables = ['area', 'plant', 'capacity'];
    private $fillables_titles = ['Saha','Bitki','Kapasite'];
    private $fillables_types = ['one','one','number'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areaCapacites = AreaCapacity::all();
        $data =array();
        $unit = Unit::where('id','=',Plant::first()->unit_id)->first();
        foreach($areaCapacites as $item){
            $d = new AreaCapacityData();
            $d->id = $item->id;
            $d->capacity = $item->capacity.' '.$unit->name;
            $d->plant = $item->plant->name;
            $d->area = $item->area->name;
            array_push($data,$d);
        }


        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => $this->fillables,
            'fillables_titles' => $this->fillables_titles,
            'empty_space' => 700,
            'data' => $data
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
        $areas = Area::all();        
        $plants = Plant::all();        
        if(count($areas) == 0)
            return redirect()->route('area.create');
        if(count($plants) == 0)
            return redirect()->route('plant.create');
        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => [$areas, $plants, 'capacity'],
            'fillables_titles' => ['Saha', 'Bitki', 'Kapasite'],
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
        $areaCapacity = new AreaCapacity;    
        $areaCapacity->capacity = $request->capacity;        
        $areaCapacity->plant()->associate($request->plants); 
        $areaCapacity->area()->associate($request->areas);         
        $areaCapacity->save();                         
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
    public function edit(AreaCapacity $areaCapacity)
    {
        $plants = Plant::all();
        $insertedPlantIds = array();                            
        array_push($insertedPlantIds, $areaCapacity->plant->id);

        $areas = Area::all();
        $insertedAreaIds = array();                            
        array_push($insertedAreaIds, $areaCapacity->area->id);


        $my_data = array(
            'title' => $this->title,
            'route' => $this->route,
            'fillables' => [[$areas, $insertedAreaIds],[$plants, $insertedPlantIds], 'capacity' ],
            'fillables_titles' => ['Sahalar','Bitkiler','Kapasite'],  
            'fillables_types' => $this->fillables_types,          
            'data' => $areaCapacity
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
    public function update(Request $request, AreaCapacity $areaCapacity)
    {
        $areaCapacity->capacity = $request->capacity;        
        $areaCapacity->plant()->associate($request->plants);          
        $areaCapacity->area()->associate($request->areas);
        $areaCapacity->save();        
        return redirect()->route($this->route.'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AreaCapacity $areaCapacity)
    {
        $areaCapacity->delete();
        return redirect()->route($this->route.'.index');
    }
}
