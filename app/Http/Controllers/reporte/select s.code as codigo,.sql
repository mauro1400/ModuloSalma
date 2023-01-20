select s.code as codigo,
                s.code_old as cod_ant,
                s.description,
                s.unit as uni_med,
                m.description as partida,
                m.code,
                t1.num_fac,
                t1.fec_ent,
                su.name as detalle,
                t1.subarticle_id,
                t1.note_entry_id,
                t1.entry_subarticle_id,
                t1.entry_date,
                t1.unit_cost as p_unitario,
                t1.amount as ingreso,     
                (t1.unit_cost * t1.amount) as t_ingreso,
                t1.date
                from (SELECT es.subarticle_id as subarticle_id,
                      ne.invoice_number as num_fac,
                      ne.id as note_entry_id,
                      ne.invoice_date as fec_ent,
                      ne.supplier_id,
                      es.id as entry_subarticle_id,
                      IFNULL(ne.note_entry_date, es.date) as entry_date,
                      es.unit_cost as unit_cost,
                      es.amount as amount,
                      es.date
                      FROM note_entries ne RIGHT JOIN entry_subarticles es on es.note_entry_id = ne.id
                      WHERE (ne.invalidate = 0 OR (ne.invalidate is null and ne.id is null))
                      AND es.subarticle_id = subarticle_id
                      AND es.invalidate = 0
                      and es.unit_cost > 0
                      ORDER BY entry_date, note_entry_id, entry_subarticle_id) t1 
                left join subarticles s on s.id=t1.subarticle_id
                left join materials m on s.material_id = m.id
                left join suppliers su on su.id=t1.supplier_id
                where t1.entry_date <= '2023-12-31'
                and t1.entry_date >= '2022-01-01' 
                and s.status = 1 AND m.status = 1;