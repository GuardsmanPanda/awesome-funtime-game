@foreach(\Illuminate\Support\Facades\DB::select("
    SELECT panorama_id FROM panorama WHERE jpg_name IS NULL
    ORDER BY random() + CASE WHEN pano_id LIKE 'CAoS%' THEN 1 ELSE 0 END LIMIT 900
") as $pano)
    <div>{{$pano->panorama_id}}</div>
@endforeach