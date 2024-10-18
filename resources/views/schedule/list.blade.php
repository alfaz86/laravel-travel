@extends('app')

@section('styles')
<style>
    .ticket-dash {
        position: relative;
        width: 100%;
        height: 2px;
        border-top: 2px dashed var(--border-color-theme);
        margin: 20px 0;
    }

    /* Semicircles on left and right side */
    .ticket-dash::before,
    .ticket-dash::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #fff;
        /* border: 2px solid var(--border-color-theme); */
        transform: translateY(-50%);
    }

    /* Left semicircle */
    .ticket-dash::before {
        left: -27px;
    }

    /* Right semicircle */
    .ticket-dash::after {
        right: -27px;
    }
</style>
@endsection


@section('content')
<div class="container mx-auto">
    @if ($schedules)
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($schedules as $schedule)
            <div class="card bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4">
                <div class="card-header mb-5">
                    <h2 class="font-semibold text-lg">{{ $schedule->bus->name }}</h2>
                </div>
                <div class="ticket-dash"></div>
                <div class="card-body">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nobis, mollitia!
                </div>
                <div class="card-footer">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus, mollitia?
                </div>
            </div>
        @endforeach
    </div>
    @else

    @endif
</div>

@include('components.alert')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        //
    });

</script>
@endsection