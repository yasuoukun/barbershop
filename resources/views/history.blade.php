<x-app-layout>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">ประวัติการจองของฉัน</h1>

    @if(session('ok'))
      <div class="bg-green-100 border border-green-300 p-3 rounded mb-4">
        {{ session('ok') }}
      </div>
    @endif

    @forelse($list as $a)
      <div class="border rounded p-3 mb-4">
        <div>บริการ: <b>{{ $a->service->name }}</b></div>
        <div>ช่าง: <b>{{ $a->barber->display_name }}</b></div>
        <div>วันเวลา: {{ $a->scheduled_at->format('d/m/Y H:i') }}</div>
        <div>สถานะ: {{ strtoupper($a->status) }}</div>

        <div class="mt-3 border-t pt-3">
          <h3 class="font-medium mb-2">ชำระเงิน</h3>

          {{-- แสดงสลิปถ้ามี --}}
          @if($a->payment && $a->payment->slip_path)
            <div class="mb-2">
              <a href="{{ asset('storage/'.$a->payment->slip_path) }}" target="_blank" class="text-blue-600 underline">
                ดูสลิปที่อัปโหลด (กดเพื่อเปิดรูปใหญ่)
              </a>
            </div>
            <img src="{{ asset('storage/'.$a->payment->slip_path) }}" alt="slip" class="w-32 border rounded mb-3">
          @endif

          {{-- ฟอร์มบันทึก/แก้ไขการชำระเงิน --}}
          <form method="POST" action="{{ route('payment.store', $a->id) }}" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block text-sm mb-1">วิธีชำระ</label>
                <select name="method" class="w-full border p-2 rounded">
                  <option value="cash"     @selected(optional($a->payment)->method==='cash')>เงินสด</option>
                  <option value="transfer" @selected(optional($a->payment)->method==='transfer')>โอน</option>
                </select>
              </div>
              <div>
                <label class="block text-sm mb-1">จำนวนเงิน (บาท)</label>
                <input type="number" name="amount" min="0" class="w-full border p-2 rounded"
                       value="{{ old('amount', optional($a->payment)->amount ?? $a->service->price) }}">
                @error('amount') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
              </div>
              <div>
                <label class="block text-sm mb-1">แนบสลิป (ถ้ามี)</label>
                <input type="file" name="slip" accept="image/*" class="w-full border p-2 rounded" id="slip-{{ $a->id }}">
                @error('slip') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- พรีวิวรูปก่อนอัปโหลด --}}
            <div id="preview-{{ $a->id }}" class="mt-2"></div>

            <button class="bg-black text-white px-4 py-2 rounded">บันทึกการชำระเงิน</button>
          </form>
        </div>
      </div>

      {{-- Script พรีวิวรูปเฉพาะรายการนี้ --}}
      <script>
      document.getElementById('slip-{{ $a->id }}')?.addEventListener('change', function(e){
        const [file] = e.target.files;
        const preview = document.getElementById('preview-{{ $a->id }}');
        if (!preview) return;
        preview.innerHTML = '';
        if (file) {
          const img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.className = 'w-32 border rounded mt-2';
          preview.appendChild(img);
        }
      });
      </script>
    @empty
      <div class="text-gray-500">ยังไม่มีการจอง</div>
    @endforelse
  </div>
</x-app-layout>
