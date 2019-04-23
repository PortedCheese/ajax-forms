<form action="{{ route($currentRoute, ['form' => $form]) }}"
      class="form-inline"
      method="get">
    @foreach($headers as $head)
        <label class="sr-only" for="{{ $head->name }}">{{ $head->title }}</label>
        <input type="text"
               class="form-control mb-2 mr-sm-2"
               id="{{ $head->name }}"
               name="{{ $head->name }}"
               value="{{ $query->get($head->name) }}"
               placeholder="{{ $head->title }}">
    @endforeach

    <label class="sr-only" for="author">Автор(email)</label>
    <input type="text"
           class="form-control mb-2 mr-sm-2"
           id="author"
           name="author"
           value="{{ $query->get('author') }}"
           placeholder="Автор(email)">

    <label class="sr-only" for="from">Дата от</label>
    <input type="date"
           class="form-control mb-2 mr-sm-2"
           id="from"
           name="from"
           value="{{ $query->get('from') }}"
           placeholder="Дата от">

    <label class="sr-only" for="to">Дата до</label>
    <input type="date"
           class="form-control mb-2 mr-sm-2"
           id="to"
           name="to"
           value="{{ $query->get('to') }}"
           placeholder="Дата до">

    <button type="submit" class="btn btn-primary mb-2">Применить</button>
    <a href="{{ route($currentRoute, ['form' => $form]) }}" class="btn btn-link mb-2">Сбросить</a>
</form>