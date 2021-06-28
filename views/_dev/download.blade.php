@foreach(\Illuminate\Support\Facades\DB::select("
    SELECT panorama_id FROM panorama WHERE jpg_name IS NULL
    ORDER BY random() + CASE WHEN panorama_id LIKE 'CAoS%' THEN 0 ELSE 1 END LIMIT 900
") as $pano)
    <div>{{$pano->panorama_id}}</div>
@endforeach