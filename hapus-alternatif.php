<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_alternatif = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (!$id_alternatif) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = mysqli_query($koneksi, "SELECT * FROM alternatif WHERE id_alternatif = '$id_alternatif'");
	$cek = mysqli_num_rows($query);

	if ($cek <= 0) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$delete_penilaian = mysqli_query($koneksi, "DELETE FROM penilaian WHERE id_alternatif = '$id_alternatif';");
		$delete_alternatif = mysqli_query($koneksi, "DELETE FROM alternatif WHERE id_alternatif = '$id_alternatif';");
		$delete_hasil = mysqli_query($koneksi, "DELETE FROM hasil WHERE id_alternatif = '$id_alternatif';");

		if ($delete_alternatif && $delete_penilaian && $delete_hasil) {
			redirect_to('list-alternatif.php?status=sukses-hapus');
		} else {
			$ada_error = 'Maaf, terjadi kesalahan saat menghapus data.';
		}
	}
}
?>

<?php
$page = "Alternatif";
require_once('template/header.php');
?>
	<?php if ($ada_error) : ?>
		<?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>	
	<?php endif; ?>
<?php
require_once('template/footer.php');
?>