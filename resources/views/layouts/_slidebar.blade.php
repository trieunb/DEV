<!--Main sidebar -->
<div class="sidebar sidebar-main sidebar-fixed">
	<div class="sidebar-content">
		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">
					<!-- Main -->
					<li>
						<a><span>@lang('menu.master')</span></a>
						<ul>
							<!--<li><a href="/master/suppliers-master-maintenance">@lang('menu.suppliers-master-maintenance')</a></li>-->
							<li><a href="/master/suppliers-master-search">@lang('menu.suppliers-master-search')</a></li>
							<!--<li><a href="/master/product-master-detail">@lang('menu.product-master-detail')</a></li>-->
							<li><a href="/master/product-master-search">@lang('menu.product-master-search')</a></li>
							<!--<li><a href="/master/component-master-detail">@lang('menu.component-master-detail')</a></li>-->
							<li><a href="/master/component-master-search">@lang('menu.component-master-search')</a></li>
							<!--<li><a href="/master/component-list-detail">@lang('menu.component-list-detail')</a></li>-->
							<li><a href="/master/component-list-search">@lang('menu.component-list-search')</a></li>
							<!--<li><a href="/master/selling-unit-price-by-client-detail">@lang('menu.selling-unit-price-by-client-detail')</a></li>-->
							<li><a href="/master/selling-unit-price-by-client-search">@lang('menu.selling-unit-price-by-client-search')</a></li>
							<!--<li><a href="/master/user-master-detail">@lang('menu.user-master-detail')</a></li>-->
							<li><a href="/master/user-master-search">@lang('menu.user-master-search')</a></li>

							<li><a href="/system-management/library-master-search">@lang('menu.library-search')</a></li>

							<li><a href="/system-management/authority">@lang('menu.system-management-authority')</a></li>
						</ul>
					</li>
					<!-- sales menu -->
					<li>
						<a><span>@lang('menu.sales')</span></a>
						<ul>
							<li><a href="/pi/pi-search">@lang('menu.pi-search')</a></li>			<!-- L0010 -->
							<li><a href="/order/order-confirm">@lang('title.order-confirm')</a></li>
							<li><a href="/accept/accept-search">@lang('menu.accept-search')</a></li>	<!-- L0020 -->
							<li><a href="/shipment/provisional-shipment-search">@lang('menu.provisional-shipment-search')</a></li>	<!--  -->
							<li><a href="/shipment/shipment-search">@lang('menu.shipment-search')</a></li>	<!-- L0040 -->
							<li><a href="/invoice/invoice-search">@lang('menu.invoice-search')</a></li>	<!-- I0050 -->
							<li><a href="/oversea-document/packing-list">@lang('title.packing-list')</a></li>
							<li><a href="/deposit/deposit-search">@lang('menu.deposit-search')</a></li>	<!-- L0070 -->
						</ul>
					</li>
					
					<li>
						<!-- <a><span>製造指示</span></a> -->
						<a><span>製造</span></a>
						<ul>
							<li><a href="/purchase-request/purchase-request-search">@lang('menu.purchase-request-search')</a></li>
							
							<li><a href="/manufactureinstruction/internalorder-search">社内発注書一覧</a></li>

							<li><a href="/component-order/order-search">@lang('menu.component-order-search')</a></li>

							<li><a href="/stocking/stocking-detail">@lang('menu.stocking-detail')</a></li>

							<li><a href="/stocking/stocking-search">仕入一覧</a></li>

							<li><a href="/manufactureinstruction/manufacturing-instruction-report">@lang('menu.manufacturing-instruction-report')</a></li>

							<li><a href="/manufactureinstruction/manufacturing-instruction-search">@lang('menu.manufacturing-instruction-search')</a></li>

							<li><a href="/manufacturing-completion-process">@lang('menu.manufacturing-completion-process')</a></li>
						</ul>
					</li>

					<li>
						<!-- <a><span>製造指示</span></a> -->
						<a><span>在庫</span></a>
						<ul>
							<li><a href="/shifting/shifting-request-search">@lang('menu.shifting-request-search')</a></li>	

							<li><a href="/stock-manage/input-output-search">@lang('menu.stock-manage-search')</a></li>

							<li><a href="/stock-manage/stock-search">@lang('menu.stock-manage-search-stock')</a></li>

						</ul>
					</li>

					<li>
						<!-- <a><span>製造指示</span></a> -->
						<a><span>作業</span></a>
						<ul>
							<li><a href="/working-time-manage/working-time-search">@lang('menu.working-time-search')</a></li>
						</ul>
					</li>
					<!-- Purchase request menu -->
					<!-- <li>
						<a><span>@lang('menu.purchase-request')</span></a>
						<ul>
							<li><a href="/purchase-request/purchase-request-detail">@lang('menu.purchase-request-detail')</a></li>	
							<li><a href="/purchase-request/purchase-request-search">@lang('menu.purchase-request-search')</a></li>	
						</ul>
					</li> -->
					<!-- Component Order menu -->
					<!-- <li>
						<a><span>@lang('menu.component-order')</span></a>
						<ul>
							<li><a href="/component-order/order-detail">@lang('menu.component-order-detail')</a></li>	
							<li><a href="/component-order/order-search">@lang('menu.component-order-search')</a></li>	
						</ul>
					</li> -->
					<!-- Shifting request menu -->
					<!-- <li>
						<a><span>@lang('menu.shifting')</span></a>
						<ul>
							<li><a href="/shifting/shifting-request-detail">@lang('menu.shifting-request-detail')</a></li>	
							<li><a href="/shifting/shifting-request-search">@lang('menu.shifting-request-search')</a></li>	
						</ul>
					</li> -->
					<!-- working time menu -->
					<!-- <li>
						<a><span>@lang('menu.working-time-manage')</span></a>
						<ul>
							<li><a href="/working-time-manage/working-time-detail">@lang('menu.working-time-detail')</a></li>	
							<li><a href="/working-time-manage/working-time-search">@lang('menu.working-time-search')</a></li>	
						</ul>
					</li> -->
					<!-- stock manage -->
					<!-- <li>
						<a><span>@lang('menu.stock-manage')</span></a>
						<ul>
							<li><a href="/stock-manage/input-output-detail">@lang('menu.stock-manage-detail')</a></li>
							<li><a href="/stock-manage/input-output-search">@lang('menu.stock-manage-search')</a></li>
							<li><a href="/stock-manage/stock-search">@lang('menu.stock-manage-search-stock')</a></li>
						</ul>
					</li> -->
					<!-- manufacturing-completion-process -->
					<!-- <li>
						<a><span>@lang('menu.manufacturing-completion-process')</span></a>
						<ul>
							<li><a href="/manufacturing-completion-process">@lang('menu.manufacturing-completion-process')</a></li>
						</ul>
					</li> -->
					<!-- stocking -->
					<!-- <li>
						<a><span>@lang('menu.stocking')</span></a>
						<ul>
							<li><a href="/stocking/stocking-detail">@lang('menu.stocking-detail')</a></li>
							<li><a href="/stocking/check-outside-ordered-products">@lang('title.stocking-check-outside-ordered-products')</a></li>
						</ul>
					</li> -->
					
					<!-- System Management -->
					<!-- <li>
						<a><span>@lang('menu.system-management')</span></a>
						<ul>
							<li><a href="/system-management/authority">@lang('menu.system-management-authority')</a></li>
							<li><a href="/system-management/library-master">@lang('menu.library-detail')</a></li>
							<li><a href="/system-management/library-master-search">@lang('menu.library-search')</a></li>
						</ul>
					</li> -->
				</ul>
			</div>
		</div>
		<!-- /main navigation -->
	</div>
</div>
<!-- /main sidebar 