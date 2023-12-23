@push('after_scripts')
    <script>
        jQuery(document).ready(function($) {
            fetch('fetch/dashboard', {
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": document.head.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then((response) => response.json())
            .then((response) => {
                response.data.map(data => $(`#${data.label}`).html(formatRupiah(data.value)))
            })
        });
        const formatRupiah = (money) => {
            const rupiah = new Intl.NumberFormat(
                'id-ID',
                {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(money);

            return rupiah.replace('Rp', '').trim();
        }
    </script>
@endpush