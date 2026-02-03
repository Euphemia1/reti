import React from "react";
import { motion } from "framer-motion";
import { GraduationCap, Award, Target, Users } from "lucide-react";

export default function WhoWeAre() {
  return (
    <section className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          {/* Left Content */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
          >
            <div className="flex items-center gap-2 mb-4">
              <div className="w-12 h-1 bg-[#f5a623] rounded-full" />
              <span className="text-[#f5a623] font-semibold uppercase tracking-wider text-sm">Who We Are</span>
            </div>
            
            <h2 className="text-4xl md:text-5xl font-bold text-[#1e3a5f] mb-6">
              Rising East Training Institute
            </h2>
            
            <div className="space-y-6 text-gray-600 leading-relaxed text-lg">
              <p>
                Rising East Training Institute (RETI) is a <span className="font-semibold text-[#1e3a5f]">TEVETA-accredited</span> vocational and professional training center based in Lusaka, Zambia. Established to respond to the growing demand for skilled professionals, RETI is committed to bridging the national skills gap in construction, engineering, and technical fields.
              </p>
              <p>
                Our programs are designed to combine theoretical knowledge with practical, hands-on training, ensuring that graduates are not only academically prepared but also job-ready for today's competitive industries.
              </p>
              <p>
                At RETI, we believe in empowerment through skills. Whether through our short intensive courses or long-term diploma programs, we aim to equip students with the competencies to excel in their chosen careers and contribute to Zambia's socio-economic development.
              </p>
            </div>
          </motion.div>

          {/* Right - Features */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="grid grid-cols-2 gap-6"
          >
            {[
              { icon: GraduationCap, title: "TEVETA Accredited", desc: "Nationally recognized certifications" },
              { icon: Award, title: "Quality Training", desc: "Industry-standard curriculum" },
              { icon: Target, title: "Job Ready", desc: "Practical hands-on skills" },
              { icon: Users, title: "Expert Faculty", desc: "Experienced instructors" }
            ].map((item, index) => (
              <div
                key={index}
                className="bg-gray-50 rounded-3xl p-6 hover:bg-[#f5a623]/10 transition-colors duration-300 group"
              >
                <div className="w-14 h-14 bg-[#1e3a5f] rounded-2xl flex items-center justify-center mb-4 group-hover:bg-[#f5a623] transition-colors">
                  <item.icon className="w-7 h-7 text-[#f5a623] group-hover:text-white transition-colors" />
                </div>
                <h3 className="text-xl font-bold text-[#1e3a5f] mb-2">{item.title}</h3>
                <p className="text-gray-600">{item.desc}</p>
              </div>
            ))}
          </motion.div>
        </div>
      </div>
    </section>
  );
}