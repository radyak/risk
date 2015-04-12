@extends('app')

@section('content')
    
<script>
    var username = "{{ Auth::user()->name }}";
</script>

<div class="container">
    
        <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                        <div class="panel-heading">{{ Lang::get('message.title.overview.matches') }}</div>

                        <div class="panel-body">
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        {{ Lang::get('message.field.match.name') }}
                                    </td>
                                    <td>
                                        {{ Lang::get('message.field.match.joinedusers') }}
                                    </td>
                                    <td>
                                        {{ Lang::get('message.field.match.startdate') }}
                                    </td>
                                    <td>
                                        {{ Lang::get('message.field.match.createdby') }}
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                
                            @foreach($matches as $match)
                                <tr>
                                    <td>
                                        {{ $match->name }}
                                    </td>
                                    <td>
                                        @foreach($match->joinedUsers as $joinedUser)
                                            {{ $joinedUser->name }}
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $match->created_at }}
                                    </td>
                                    <td>
                                        {{ $match->createdBy->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('match.join', $match->id) }}">{{ Lang::get('message.link.match.join') }}</a>
                                    </td>
                                    <td>
                                        @if(Auth::user()->id == $match->createdBy->id)
                                            <a href="{{ route('match.cancel', $match->id) }}">{{ Lang::get('message.link.match.cancel') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        </div>
                </div>
        </div>
</div>
@endsection
