@component('layouts.app')
@slot('header')
<div class="flex">
  @if($user->id === Auth::user()->id)
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    あなたの課題
  </h2>
  @else
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ $user->name }}さんの課題
  </h2>
  @endif
  <p class="ml-8 text-gray-500">作業中の課題数:{{ $working_task_count }}</p>
  <p class="ml-4 text-gray-500">完了した課題数:{{ $completed_task_count }}</p>
</div>
@endslot

@foreach ( $timelines as $task )
<div class="container m-auto">
  <div class="bg-{{ $task->status_color }}-100 border rounded-sm border-{{ $task->status_color }}-300 m-5">
    <div class="p-3 border-b border-{{ $task->status_color }}-300 flex justify-between">
      <div class="hover:text-{{ $task->status_color }}-700">
        <a href="{{ url('users/' . $task->user_id ) }}" class="">{{ $task->user->name }}</a>
      </div>
      <div class="flex">
        <div class="text-gray-400 mx-2">{{ $task->updated_at->format('Y-m-d H:i') }}</div>
      </div>
    </div>
    <div class="p-3 ">
      <div class="flex justify-between">
        <div><a href="{{ url('tasks/' . $task->id)}}" class="text-lg font-bold hover:text-{{ $task->status_color }}-700">{{ $task->task_name }}</a></div>
        @if ($task->status === 1 && $task->user->id === Auth::user()->id)
        <div>
          <form method="POST" action="{{ url('tasks/' . $task->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" class="form-input mt-1 block w-full" name="task_name" id="task_name" value="{{ $task->task_name }}">
            <input type="hidden" name="status" id="status" value="2">
            <input type="hidden" name="due_date" id="due_date" value="{{ $task->due_date }}">
            <button class="px-2 border rounded border-transparent border-gray-300 text-gray-700 focus:outline-none focus:border-transparent bg-blue-300 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-opacity-50">
              状態を完了に変更!!
            </button>
          </form>
        </div>
        @endif
      </div>
      <div>
        <p>状態:{{ $task->status_name }}</p>
      </div>
      <div class="flex justify-between">
        <div>
          <p>締切:{{ $task->due_date }}</p>
        </div>

        <div class="flex">
          <div class="mr-3 text-lg text-gray-400"><i class="far fa-comment fa-fw"></i>{{ count($task->comments) }}</div>
          <div class="mr-2 text-lg flex">

            @if (!in_array(Auth::user()->id, array_column($task->favorites->toArray(), 'user_id'), TRUE))
            <div>
              <form method="POST" action="{{ url('favorites/') }}" class="mb-0">
                @csrf

                <input type="hidden" name="task_id" value="{{ $task->id }}">
                <button type="submit" class="text-gray-400"><i class="far fa-heart fa-fw"></i></button>
              </form>
            </div>
            <div class="text-gray-400">
              {{ count($task->favorites) }}
            </div>
            @else
            <div>
              <form method="POST" action="{{ url('favorites/' .array_column($task->favorites->toArray(), 'id', 'user_id')[Auth::user()->id]) }}" class="mb-0">
                @csrf
                @method('DELETE')

                <button type="submit" class="text-red-600"><i class="fas fa-heart fa-fw"></i></button>
              </form>
            </div>
            <div class="text-red-600">
              {{ count($task->favorites) }}
            </div>
            @endif
          </div>
          @if ($task->user->id === Auth::user()->id)
          <div class="relative group">
            <div class="text-lg text-gray-400 hover:text-gray-600">
              <i class="fas fa-ellipsis-v fa-fw"></i>
            </div>
            <div class="absolute w-15 px-2 py-1 border rounded border-gray-200 invisible group-hover:visible bg-white">
              <a class="text-lg text-gray-400 hover:text-gray-700" href="{{ url('tasks/' . $task->id . '/edit') }}">編集</a>
              <form method="POST" action="{{ url('tasks/' . $task->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-lg text-red-400 hover:text-red-700">削除</button>
              </form>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

@endcomponent