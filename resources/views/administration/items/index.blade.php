<!DOCTYPE html>
<html>
<head>
    <title>Laravel DataTables</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>
<body>
    <div class="container mt-5">
        <table id="example" class="display table table-bordered mt-4" style="width:100%">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Aanschafdatum</th>
                    <th>Tiernummer</th>
                    <th>Status</th>
                    <th>Picture</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->group->name }}</td>
                        <td>
                            <form action="{{ route('administration.items.updateName', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>{{ $item->aanschafdatum }}</td>
                        <td>{{ $item->tiernummer }}</td>
                        <td>
                            <form action="{{ route('administration.items.updateStatus', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="in_storage" {{ $item->status == 'in_storage' ? 'selected' : '' }}>In Storage</option>
                                    <option value="repairing" {{ $item->status == 'repairing' ? 'selected' : '' }}>Repairing</option>
                                    <option value="out_of_order" {{ $item->status == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($item->picture)
                                <img src="{{ asset('storage/' . $item->picture) }}" alt="{{ $item->name }}" width="50" height="50">
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                columnDefs: [
                    {
                        targets: [0],
                        orderData: [0, 1]
                    },
                    {
                        targets: [1],
                        orderData: [1, 0]
                    },
                    {
                        targets: [4],
                        orderData: [4, 0]
                    }
                ]
            });
        });
    </script>
</body>
</html>
