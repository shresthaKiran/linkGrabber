<table class="table" id="linkTable">
    <thead class="thead-inverse">
    <tr>
        <td>Link</td>
        <td>Shared By</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $index => $messages)
        <tr>
            <td class="{{sprintf('link-%s',$index)}}"><a href="{{array_get($messages,'link')}}" target="_blank">{{array_get($messages,'link')}}</a></td>
            <td>{{array_get($messages,'sharedBy')}}</td>
            <td>
                <button class="btn" data-clipboard-target="{{sprintf('.link-%s',$index)}}">
                    Copy
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>