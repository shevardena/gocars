<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>შეხსენება ავტომობილების ჩამოსვლის თარიღზე</title>
</head>
<body>
<h1>ჩამოთვლილი ავტომობილების ჩამოყვანიდან გავიდა {{ $day }}-ე დღე</h1>
@foreach($cars as $car)
    <p>
        @forelse($car->getMedia('car_images') as $image)
            <img src="{{ $message->embed($image->getPath()) }}" width="100">
        @empty
        @endforelse
    </p>
    Manufacturer: <strong>{{ $car->model->make->name ?? 'Unknown' }}</strong><br>
    Model: <strong>{{ $car->model->name ?? 'Unknown' }}</strong><br>
    Year: <strong>{{ $car->year }}</strong><br>
    VIN: <strong>{{ $car->vin }}</strong><br>
    <a href="{{ url('car/' . $car->id . '/' . $car->slug ) }}">საიტზე ნახვა</a><br>
    </p>
@endforeach
</body>
</html>
