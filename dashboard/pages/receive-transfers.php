<?php
require_once __DIR__ . "/../includes/header.php";
?>

<section class="w-full">

    <!-- Page Header -->
    <article class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h1 class="font-title font-bold text-2xl">
                Receive Product Transfers
            </h1>

            <p class="text-sm text-gray-600">
                Review pending transfers from warehouse and accept or reject them.
            </p>

        </div>

    </article>

    <!-- Response Box -->

    <div
        id="responseBox"
        class="hidden mb-5 px-4 py-3 rounded-xl text-sm font-medium">
    </div>

    <!-- Search & Filter -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg mb-6">

        <div class="grid md:grid-cols-3 gap-5 p-6">

            <div>

                <label class="label">

                    Search

                </label>

                <input
                    type="text"
                    id="searchInput"
                    class="input"
                    placeholder="Product, Store or Reference">

            </div>

            <div>

                <label class="label">

                    Movement Type

                </label>

                <select
                    id="movementFilter"
                    class="input">

                    <option value="">

                        All

                    </option>

                    <option value="send">

                        Send

                    </option>

                    <option value="return">

                        Return

                    </option>

                </select>

            </div>

            <div>

                <label class="label">

                    Status

                </label>

                <select
                    id="statusFilter"
                    class="input">

                    <option value="pending">

                        Pending

                    </option>

                    <option value="accepted">

                        Accepted

                    </option>

                    <option value="rejected">

                        Rejected

                    </option>

                </select>

            </div>

        </div>

    </article>

    <!-- Table -->

    <article
        class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead
                    class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Product

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Store

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Qty

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Type

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Status

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Reference

                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">

                            Date

                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody
                    id="transferTable"
                    class="divide-y divide-gray-100">

                    <tr>

                        <td
                            colspan="8"
                            class="py-10 text-center text-gray-500">

                            Loading transfers...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </article>

    <!-- Pagination -->

    <div
        id="pagination"
        class="hidden flex justify-between items-center mt-6">

        <button
            id="prevPage"
            class="px-4 py-2 rounded-lg border">

            Previous

        </button>

        <span
            id="pageInfo"
            class="text-sm text-gray-600"></span>

        <button
            id="nextPage"
            class="px-4 py-2 rounded-lg border">

            Next

        </button>

    </div>

</section>

<!-- ========================= -->
<!-- RECEIVE MODAL -->
<!-- ========================= -->

<div
    id="receiveModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div
        class="bg-white rounded-2xl w-full max-w-3xl shadow-xl">

        <div
            class="flex justify-between items-center border-b px-6 py-4">

            <h2
                class="font-title font-bold text-lg">

                Receive Transfer

            </h2>

            <button
                id="closeModal"
                class="text-3xl leading-none">

                &times;

            </button>

        </div>

        <form
            id="receiveForm"
            class="p-6 space-y-5">

            <input
                type="hidden"
                id="transfer_id">

            <div class="grid md:grid-cols-2 gap-5">

                <div>

                    <label class="label">

                        Product

                    </label>

                    <input
                        id="product_name"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div>

                    <label class="label">

                        Barcode

                    </label>

                    <input
                        id="barcode"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div>

                    <label class="label">

                        Store

                    </label>

                    <input
                        id="store_name"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div>

                    <label class="label">

                        Quantity

                    </label>

                    <input
                        id="quantity"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div>

                    <label class="label">

                        Movement

                    </label>

                    <input
                        id="movement_type"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div>

                    <label class="label">

                        Reference

                    </label>

                    <input
                        id="reference_no"
                        class="input bg-gray-100"
                        readonly>

                </div>

                <div class="md:col-span-2">

                    <label class="label">

                        Remarks

                    </label>

                    <textarea
                        id="remarks"
                        rows="4"
                        class="input"
                        placeholder="Optional remarks"></textarea>

                </div>

                <div class="md:col-span-2">

                    <label class="label">

                        Decision

                    </label>

                    <select
                        id="status"
                        class="input">

                        <option value="accepted">

                            Accept Transfer

                        </option>

                        <option value="rejected">

                            Reject Transfer

                        </option>

                    </select>

                </div>

            </div>

            <div
                class="flex justify-end gap-3 pt-3">

                <button
                    type="button"
                    id="cancelModal"
                    class="px-5 py-2 rounded-xl border">

                    Cancel

                </button>

                <button
                    type="submit"
                    id="receiveBtn"
                    class="px-6 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">

                    Submit

                </button>

            </div>

        </form>

    </div>

</div>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>

<script src="receive-transfers.js"></script>