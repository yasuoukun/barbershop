<x-app-layout>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">จองคิวตัดผม</h1>

    <form method="POST" action="{{ route('booking.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block mb-1">บริการ</label>
        <select name="service_id" class="w-full border p-2 rounded" required>
          @foreach($services as $s)
            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->price }} บาท / {{ $s->duration_minutes }} นาที)</option>
          @endforeach
        </select>
        @error('service_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block mb-1">เลือกช่าง</label>
        <select name="barber_id" class="w-full border p-2 rounded" required>
          @foreach($barbers as $b)
            <option value="{{ $b->id }}">{{ $b->display_name }}</option>
          @endforeach
        </select>
        @error('barber_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block mb-1">วันที่</label>
          <input type="date" name="date" class="w-full border p-2 rounded" required>
          @error('date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="block mb-1">เวลา</label>
          <input type="time" name="time" class="w-full border p-2 rounded" required>
          @error('time') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
      </div>

      <button class="bg-black text-white px-4 py-2 rounded">ยืนยันจอง</button>
    </form>
  </div>
</x-app-layout>
