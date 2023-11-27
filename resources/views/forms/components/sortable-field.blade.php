<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
        x-load-css="['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css']"
        x-load-js="['https://raw.githack.com/SortableJS/Sortable/master/Sortable.js']"
        x-init="(() => {
            // List with handle
            new Sortable(document.getElementById('listWithHandle'), {
              handle: '.glyphicon-move',
              animation: 150,
              store: {
                get: function (sortable) {
                  var order = localStorage.getItem(sortable.options.group.name);
                  return order ? order.split('|') : [];
                },
                set: function (sortable) {
                  var order = sortable.toArray();


                  console.log('asa', sortable, order);
                  localStorage.setItem(sortable.options.group.name, order.join('|'));
                  document.getElementById('order').value = JSON.stringify(
                    sortable.toArray()
                  );
                },
              },
            });

        })()"
        x-cloak
        wire:ignore>
        <!-- Interact with the `state` property in Alpine.js -->

        <!-- List with handle -->
        <div id="listWithHandle" class="list-group">
            <div class="list-group-item" data-id="john">
                <span class="badge">14</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                Drag me by the handle
            </div>
            <div class="list-group-item" data-id="james">
                <span class="badge">2</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                You can also select text
            </div>
            <div class="list-group-item" data-id="year">
                <span class="badge">1</span>
                <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                Best of both worlds!
            </div>
        </div>
        <input id="order" type="hidden" name="order" />
    </div>
</x-dynamic-component>
