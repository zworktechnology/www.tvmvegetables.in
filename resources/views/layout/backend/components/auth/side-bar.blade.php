<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="{{ Route::is('home', 'home.datefilter') ? 'active' : '' }}">
                            <a href="{{ route('home') }}"><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="submenu-open">
                    <h6 class="submenu-hdr">Bill Management</h6>
                    <ul>
                        <li class="{{ Route::is('purchase.index', 'purchase.store', 'purchase.create', 'purchase.update', 'purchase.delete', 'purchase.print_view', 'purchase.branchdata', 'purchase.datefilter', 'purchase.invoice', 'purchase.invoice_update', 'purchase.invoiceedit', 'purchase.invoiceedit_update') ? 'active' : '' }}">
                            <a href="/purchasebranch/1"><i data-feather="shopping-bag"></i><span>Purchase</span></a>
                        </li>
                        <li class="{{ Route::is('sales.index', 'sales.store', 'sales.create', 'sales.edit', 'sales.update', 'sales.invoice', 'sales.invoice_update', 'sales.delete', 'sales.branchdata', 'sales.print_view', 'sales.report', 'sales.datefilter') ? 'active' : '' }}">
                            <a href="/salesbranch/1"><i data-feather="shopping-cart"></i><span>Sales</span></a>
                        </li>
                    </ul>
                </li> --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li class="{{ Route::is('customer.index', 'customer.store', 'customer.edit', 'customer.delete', 'customer.checkduplicate', 'customer.view', 'customer.viewfilter', 'customer.branchdata') ? 'active' : '' }}">
                            <a href="{{ route('customer.branchdata', ['branch_id' => '1']) }}"><i data-feather="users"></i><span>Customers</span></a>
                        </li>
                        <li class="{{ Route::is('sales.order.branch', 'salesorder.salesorder_index', 'salesorder.salesorder_store', 'salesorder.salesorder_create', 'salesorder.salesorder_edit', 'salesorder.salesorder_update', 'salesorder.salesorder_branchdata', 'salesorder.salesorder_printview', 'salesorder.salesorder_datefilter') ? 'active' : '' }}">
                            <a href="/salesorderbranch/1"><i data-feather="shopping-cart"></i><span>Sales</span></a>
                        </li>
                        <li class="{{ Route::is('sales.payment.branch', 'salespayment.index', 'salespayment.store', 'salespayment.create', 'salespayment.store', 'salespayment.edit', 'salespayment.update', 'salespayment.branchdata', 'salespayment.datefilter') ? 'active' : '' }}">
                            <a href="/salespaymentbranch/1"><i data-feather="dollar-sign"></i><span>Payment</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li class="{{ Route::is('supplier.index', 'supplier.store', 'supplier.edit', 'supplier.delete', 'supplier.checkbalance', 'supplier.checkduplicate', 'supplier.view', 'supplier.viewfilter', 'supplier.branchdata') ? 'active' : '' }}">
                            <a href="{{ route('supplier.branchdata', ['branch_id' => '1']) }}"><i data-feather="users"></i><span>Supliers</span></a>
                        </li>
                        <li class="{{ Route::is('purchase.order.branch', 'purchaseorder.purchaseorder_index', 'purchaseorder.purchaseorder_branchdata', 'purchaseorder.purchaseorder_datefilter', 'purchaseorder.purchaseorder_create', 'purchaseorder.purchaseorder_store', 'purchaseorder.purchaseorder_edit', 'purchaseorder.purchaseorder_update', 'purchaseorder.purchaseorder_invoice', 'purchaseorder.purchaseorder_invoiceupdate', 'purchaseorder.purchaseorder_invoiceedit', 'purchaseorder.purchaseorder_invoiceeditupdate', 'purchaseorder.purchaseorder_printview') ? 'active' : '' }}">
                            <a href="/purchaseorderbranch/1"><i data-feather="shopping-bag"></i><span>Purchase</span></a>
                        </li>
                        <li class="{{ Route::is('purchase.payment.branch', 'purchasepayment.index', 'purchasepayment.store', 'purchasepayment.create', 'purchasepayment.edit', 'purchasepayment.update', 'purchasepayment.delete', 'purchasepayment.branchdata', 'purchasepayment.datefilter') ? 'active' : '' }}">
                            <a href="/purchasepaymentbranch/1"><i data-feather="dollar-sign"></i></i><span>Payment</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li class="{{ Route::is('expense.branch', 'expence.index', 'expence.store', 'expence.create', 'expence.edit', 'expence.update', 'expence.branchdata', 'expence.datefilter') ? 'active' : '' }}">
                            <a href="/expensebranch/1"><i data-feather="file-text"></i><span>Expence</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li class="{{ Route::is('purchase.report', 'purchase.report_view') ? 'active' : '' }}">
                            <a href="{{ route('purchase.report') }}"><i data-feather="pie-chart"></i><span>Purchase Report</span></a>
                        </li>
                        <li class="{{ Route::is('sales.report', 'sales.report_view') ? 'active' : '' }}">
                            <a href="{{ route('sales.report') }}"><i data-feather="bar-chart-2"></i><span>Sales Report</span></a>
                        </li>
                        <li class="{{ Route::is('expence.report', 'expence.report_view') ? 'active' : '' }}">
                            <a href="{{ route('expence.report') }}"><i data-feather="file"></i><span>Expense Report</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">General</h6>
                    <ul>
                        <li class="{{ Route::is('branch.index', 'branch.store', 'branch.edit', 'branch.delete') ? 'active' : '' }}">
                            <a href="{{ route('branch.index') }}"><i data-feather="map"></i><span>Branch</span></a>
                        </li>
                        <li class="{{ Route::is('unit.index', 'unit.store', 'unit.edit', 'unit.delete') ? 'active' : '' }}" hidden>
                            <a href="{{ route('unit.index') }}"><i data-feather="map"></i><span>Unit</span></a>
                        </li>
                        <li class="{{ Route::is('bank.index', 'bank.store', 'bank.edit') ? 'active' : '' }}">
                            <a href="{{ route('bank.index') }}"><i data-feather="credit-card"></i><span>Bank</span></a>
                        </li>
                        <li class="{{ Route::is('product.index', 'product.store', 'product.edit' ) ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}"><i data-feather="codesandbox"></i><span>Product</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        @hasrole('Super-Admin')
                        <li class="{{ Route::is('invite.index', 'invite.store') ? 'active' : '' }}">
                            <a href="{{ route('invite.index') }}"><i data-feather="user"></i><span>Managers</span></a>
                        </li>
                        @endhasrole
                        <li class="{{ Route::is('profile') ? 'active' : '' }}">
                            <a href="{{ route('profile') }}"><i data-feather="user-check"></i><span>Upadte Profile</span></a>
                        </li>
                        <li class="{{ Route::is('settings') ? 'active' : '' }}">
                            <a href="{{ route('settings') }}"><i data-feather="settings"></i><span>Change password</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
