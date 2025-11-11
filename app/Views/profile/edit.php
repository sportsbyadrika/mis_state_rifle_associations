<?php
$title = 'Manage Profile - KSRA MIS';
$heading = 'Manage Your Profile';
$photoUrl = !empty($user['photo_path'] ?? '') ? asset_url($user['photo_path']) : 'https://via.placeholder.com/160';
ob_start();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css">
<div class="max-w-3xl space-y-6">
    <?php if (!empty($status ?? '')): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            <?= htmlspecialchars($status); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors ?? [])): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars(url_to('profile')); ?>" class="card p-6 space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <img src="<?= htmlspecialchars($photoUrl); ?>" alt="Profile photo" class="h-24 w-24 rounded-full object-cover border-2 border-white/60 shadow" id="profile-photo-preview">
            <div class="space-y-3">
                <p class="text-sm text-muted">Upload a clear, square image. Images larger than 5&nbsp;MB will be rejected.</p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="change-photo-button" class="button-secondary">Change Photo</button>
                    <input type="file" id="photo-input" accept="image/*" class="hidden">
                    <input type="hidden" name="photo_data" id="photo-data">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-muted">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="<?= htmlspecialchars($user['name'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-muted">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($user['email'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-muted">Phone Number</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="text-lg font-semibold section-heading">Update Password</h2>
            <p class="text-sm text-muted">Leave the password fields blank to keep your current password.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-muted">Current Password</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="current-password"
                >
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-muted">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="new-password"
                >
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-muted">Confirm New Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="button-primary inline-flex items-center justify-center gap-2 px-6 py-2">
                Save Changes
            </button>
        </div>
    </form>
</div>
<div id="photo-crop-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 px-4 py-6">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-2xl space-y-6">
        <h3 class="text-lg font-semibold text-slate-800">Adjust Profile Photo</h3>
        <div class="relative w-full overflow-hidden rounded-xl bg-slate-100">
            <img src="" alt="Crop preview" id="crop-image" class="block max-h-[480px] w-full object-contain">
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" class="button-secondary" id="crop-cancel">Cancel</button>
            <button type="button" class="button-primary" id="crop-save">Apply Photo</button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const changeButton = document.getElementById('change-photo-button');
        const fileInput = document.getElementById('photo-input');
        const photoDataInput = document.getElementById('photo-data');
        const modal = document.getElementById('photo-crop-modal');
        const cropImage = document.getElementById('crop-image');
        const cancelButton = document.getElementById('crop-cancel');
        const saveButton = document.getElementById('crop-save');
        const preview = document.getElementById('profile-photo-preview');
        let cropper = null;

        if (!changeButton || !fileInput || !photoDataInput || !modal || !cropImage || typeof Cropper === 'undefined') {
            return;
        }

        function closeModal() {
            modal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = '';
        }

        function openModal(imageSource) {
            modal.classList.remove('hidden');
            cropImage.src = imageSource;

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                movable: false,
                zoomable: true,
                scalable: false,
            });
        }

        changeButton.addEventListener('click', function (event) {
            event.preventDefault();
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please choose an image file.');
                this.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Please choose an image that is 5 MB or smaller.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const result = e.target && e.target.result ? e.target.result.toString() : '';
                if (result !== '') {
                    openModal(result);
                }
            };
            reader.readAsDataURL(file);
        });

        cancelButton.addEventListener('click', function () {
            closeModal();
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        saveButton.addEventListener('click', function () {
            if (!cropper) {
                return;
            }

            const canvas = cropper.getCroppedCanvas({ width: 600, height: 600, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
            if (!canvas) {
                return;
            }

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            photoDataInput.value = dataUrl;
            preview.src = dataUrl;
            closeModal();
        });

        document.addEventListener('keyup', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
