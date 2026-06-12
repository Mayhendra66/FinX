function formatIDR(input) {
    // Ambil posisi cursor sebelum format (opsional)
    let raw = input.value.replace(/\D/g, "");
    if (raw) {
        input.value = "Rp " + new Intl.NumberFormat("id-ID").format(raw);
    } else {
        input.value = "";
    }
}

function getRawValue(formattedValue) {
    return formattedValue.replace(/\D/g, "");
}