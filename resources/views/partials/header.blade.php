<div class="menu_top">
    <a href="{{ url('/basic') }}">Basic</a>
    <a href="{{ url('/probability') }}">Probability</a>
    <a href="{{ url('/inferential') }}">Inferential</a>
    <a href="{{ url('/visualization') }}">Visualization</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>