@forelse($studieRoutes as $studieRoute)
    <hr>

    <div class="panel-body">
        <h3>
            <b>
                <a href="{{ route('studieroute.show', $studieRoute) }}">
                    {{ $studieRoute->name }}
                </a>
            </b>
        </h3>

        <p>{{ $studieRoute->description }}</p>

    </div>
@empty
    <span>No results</span>
@endforelse