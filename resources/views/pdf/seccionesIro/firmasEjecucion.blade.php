<table>
	<tr>
        <td style="height:0px; border:0px;"></td>
		<td style="height:0px; border:0px;"></td>
	</tr>
	<tr>
		<td><p class="negrita cursiva"> RESPONSABLE EJECUCIÓN TRABAJOS</p>
		</td>
		<td>
		<p>VoBo</p>	
		</td>
	</tr>
	<tr>
		<td>
		<img src="{{(count($foto1) > 0 ? $foto1[0] : null)}}" style="position: absolute;width: 50mm;top:-8mm;">
		<p class="negrita">_____________________________________________</p>
		</td>
		<td>
		<p class="negrita">_____________________________________________</p>
		</td>
	</tr>
	<tr>
		<td>
		<p class="negrita cursiva">Firma</p>
		<p></p>
		</td>
		<td>
		<p class="negrita cursiva">Firma</p>
		<p></p>	
		</td>
	</tr>	
	<tr>
		<td>
		<p class="negrita cursiva">Nombre</p>
		<p>{{strtoupper($lider[0]->nombre)}}</p>
		</td>
		<td><p class="negrita cursiva">Nombre</p>
		<p></p>
		</td>
	</tr>
	<tr>
		<td><p class="negrita cursiva">CC</p>
		<p>{{strtoupper($lider[0]->cedula)}}</p>
		</td>
		<td><p class="negrita cursiva">CC</p>
		<p></p>
		</td>
	</tr>
</table>