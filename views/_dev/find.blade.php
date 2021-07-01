<table >
    <tr>
        <th>Country Code</th>
        <th>region_name</th>
        <th>state_name</th>
        <th>state_district_name</th>
        <th>county</th>
        <th>city_name</th>
        <th>Distance</th>
    </tr>
    @foreach($data as $dat)
        <tr>
            <td>{{$dat->extended_country_code}}</td>
            <td>{{$dat->region_name}}</td>
            <td>{{$dat->state_name}}</td>
            <td>{{$dat->state_district_name}}</td>
            <td>{{$dat->county_name}}</td>
            <td>{{$dat->city_name}}</td>
            <td>{{$dat->distance}}</td>
        </tr>
    @endforeach
</table> 